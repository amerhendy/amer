<?php
namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel\Traits;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

trait Validation
{
    /**
     * Adds the required rules from an array and allows validation of that array.
     *
     * @param  array  $requiredFields
     */
    public function setValidationFromArray(array $rules, array $messages = [])
    {
        $this->setRequiredFields($rules);
        $this->setOperationSetting('validationRules', array_merge($this->getOperationSetting('validationRules') ?? [], $rules));
        $this->setOperationSetting('validationMessages', array_merge($this->getOperationSetting('validationMessages') ?? [], $messages));
    }
    public function setValidationFromFields()
    {
        $fields = $this->getOperationSetting('fields');
        $rules = $this->getValidationRulesFromFieldsAndSubfields($fields);// (eg. ['name' => 'required|min:2'])
        $messages = $this->getValidationMessagesFromFieldsAndSubfields($fields);// (eg. ['title.required' => 'You gotta write smth man.'])
        $this->setValidationFromArray($rules, $messages);
    }
    private function getValidationRulesFromFieldsAndSubfields($fields)
    {
        $rules = collect($fields)
            ->filter(function ($value, $key) {
                return array_key_exists('validationRules', $value) || array_key_exists('subfields', $value);
            })->map(function ($item, $key) {
                $validationRules = [];
                // only keep the rules, not the entire field definition
                if (isset($item['validationRules'])) {
                    $validationRules[$key] = $item['validationRules'];
                }
                // add validation rules for subfields
                if (array_key_exists('subfields', $item)) {
                    $subfieldsWithValidation = array_filter($item['subfields'], function ($subfield) {
                        return array_key_exists('validationRules', $subfield);
                    });

                    foreach ($subfieldsWithValidation as $subfield) {
                        $validationRules[$item['name'].'.*.'.$subfield['name']] = $subfield['validationRules'];
                    }
                }

                return $validationRules;
            })->toArray();
        return array_merge(...array_values($rules));
    }

    /**
     * Return the messages for the fields and subfields in the  panel.
     *
     * @param  array  $fields
     * @return array
     */
    private function getValidationMessagesFromFieldsAndSubfields($fields)
    {
        $messages = [];
        collect($fields)
            ->filter(function ($value, $key) {
                // only keep fields where 'validationMessages' OR there are subfields
                return array_key_exists('validationMessages', $value) || array_key_exists('subfields', $value);
            })->each(function ($item, $key) use (&$messages) {
                if (isset($item['validationMessages'])) {
                    foreach ($item['validationMessages'] as $rule => $message) {
                        $messages[$key.'.'.$rule] = $message;
                    }
                }
                // add messages from subfields
                if (array_key_exists('subfields', $item)) {
                    $subfieldsWithValidationMessages = array_filter($item['subfields'], function ($subfield) {
                        return array_key_exists('validationRules', $subfield);
                    });

                    foreach ($subfieldsWithValidationMessages as $subfield) {
                        foreach ($subfield['validationMessages'] ?? [] as $rule => $message) {
                            $messages[$item['name'].'.*.'.$subfield['name'].'.'.$rule] = $message;
                        }
                    }
                }
            })->toArray();

        return $messages;
    }

    /**
     * Mark a FormRequest file as required for the current operation, in Settings.
     * Adds the required rules to an array for easy access.
     *
     * @param  string  $class  Class that extends FormRequest
     */
    public function setValidationFromRequest($class)
    {
        $this->setFormRequest($class);
        $this->setRequiredFields($class);
    }

    /**
     * Mark a FormRequest file as required for the current operation, in Settings.
     * Adds the required rules to an array for easy access.
     *
     * @param  string|array  $classOrRulesArray  Class that extends FormRequest or array of validation rules
     * @param  array  $messages  Array of validation messages.
     */
    public function setValidation($classOrRulesArray = false, $messages = [])
    {
        if (! $classOrRulesArray) {
            $this->setValidationFromFields();
        } elseif (is_array($classOrRulesArray)) {
            $this->setValidationFromArray($classOrRulesArray, $messages);
        } elseif (is_string($classOrRulesArray) && class_exists($classOrRulesArray) && is_a($classOrRulesArray, FormRequest::class, true)) {
            $this->setValidationFromRequest($classOrRulesArray);
        } else {
            abort(500, 'Please pass setValidation() nothing, a rules array or a FormRequest class.');
        }
    }

    /**
     * Remove the current FormRequest from configuration, so it will no longer be validated.
     */
    public function unsetValidation()
    {
        $this->setOperationSetting('formRequest', false);
        $this->setOperationSetting('validationRules', []);
        $this->setOperationSetting('validationMessages', []);
        $this->setOperationSetting('requiredFields', []);
    }

    /**
     * Remove the current FormRequest from configuration, so it will no longer be validated.
     */
    public function disableValidation()
    {
        $this->unsetValidation();
    }

    /**
     * Mark a FormRequest file as required for the current operation, in Settings.
     *
     * @param  string  $class  Class that extends FormRequest
     */
    public function setFormRequest($class)
    {
        $this->setOperationSetting('formRequest', $class);
    }

    /**
     * Get the current form request file, in any.
     * Returns null if no FormRequest is required for the current operation.
     *
     * @return string Class that extends FormRequest
     */
    public function getFormRequest()
    {
        return $this->getOperationSetting('formRequest');
    }

    /**
     * Run the authorization and validation for the current  panel.
     * That authorization is gathered from 3 places:
     * - the FormRequest when provided.
     * - the rules added in the controller.
     * - the rules defined in the fields itself.
     *
     * @return \Illuminate\Http\Request
     */
    public function validateRequest()
    {
        $formRequest = $this->getFormRequest();

        $rules = $this->getOperationSetting('validationRules') ?? [];
        $messages = $this->getOperationSetting('validationMessages') ?? [];

        if ($formRequest) {
            // when there is no validation in the fields, just validate the form request.
            if (empty($rules)) {
                return app($formRequest);
            }

            [$formRequest, $extendedRules, $extendedMessages] = $this->mergeRequestAndFieldRules($formRequest, $rules, $messages);

            // validate the complete request with FormRequest + controller validation + field validation (our anonymous class)
            return $this->checkRequestValidity($extendedRules, $extendedMessages, $formRequest);
        }

        return ! empty($rules) ? $this->checkRequestValidity($rules, $messages) : $this->getRequest();
    }

    /**
     * Merge the form request validation with the fields validation.
     *
     * @param  FormRequest  $request
     * @param  array|null  $rules
     * @param  array|null  $messages
     * @return array
     */
    public function mergeRequestAndFieldRules($request, $rules = null, $messages = null)
    {
        $rules = $rules ?? $this->getOperationSetting('validationRules') ?? [];
        $messages = $messages ?? $this->getOperationSetting('validationMessages') ?? [];

        $request = (new $request)->createFrom($this->getRequest());
        $extendedRules = $this->mergeRules($request, $rules);
        $extendedMessages = array_merge($messages, $request->messages());

        return [$request, $extendedRules, $extendedMessages];
    }

    /**
     * Return an array containing the request rules and the field/controller rules merged.
     * The rules in request will take precedence over the ones in controller/fields.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @return array
     */
    private function mergeRules($request, $rules)
    {
        $extendedRules = [];
        $requestRules = $this->getRequestRulesAsArray($request);
        $rules = array_map(function ($ruleDefinition) {
            return is_array($ruleDefinition) ? $ruleDefinition : explode('|', $ruleDefinition);
        }, $rules);

        foreach ($requestRules as $ruleKey => $rule) {
            $extendedRules[$ruleKey] = array_key_exists($ruleKey, $rules) ? array_merge($rule, $rules[$ruleKey]) : $rule;
            unset($rules[$ruleKey]);
        }

        return array_merge($rules, $extendedRules);
    }

    /**
     * Return the request rules as an array of rules if developer provided a rule string configuration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function getRequestRulesAsArray($request)
    {
        $requestRules = [];
        foreach ($request->rules() as $ruleKey => $rule) {
            $requestRules[$ruleKey] = is_array($rule) ? $rule : explode('|', $rule);
        }

        return $requestRules;
    }

    /**
     * Checks if the request is valid against the rules.
     *
     * @param  array  $rules
     * @param  array  $messages
     * @param  \Illuminate\Http\Request|null  $request
     * @return \Illuminate\Http\Request
     */
    private function checkRequestValidity($rules, $messages, $request = null)
    {
        $request = $request ?? $this->getRequest();
        $request->validate($rules, $messages);

        return $request;
    }

    /**
     * Parse a FormRequest class, figure out what inputs are required
     * and store this knowledge in the current object.
     *
     * @param  string|array  $classOrRulesArray  Class that extends FormRequest or rules array
     */
    public function setRequiredFields($classOrRulesArray)
    {
        $requiredFields = $this->getOperationSetting('requiredFields') ?? [];

        if (is_array($classOrRulesArray)) {
            $rules = $classOrRulesArray;
        } else {
            $formRequest = new $classOrRulesArray();
            $rules = $formRequest->rules();
        }

        if (count($rules)) {
            foreach ($rules as $key => $rule) {
                if (
                    (is_string($rule) && strpos($rule, 'required') !== false && strpos($rule, 'required_') === false) ||
                    (is_array($rule) && array_search('required', $rule) !== false && array_search('required_', $rule) === false)
                ) {
                    if (Str::contains($key, '.')) {
                        $key = Str::dotsToSquareBrackets($key, ['*']);
                    }

                    $requiredFields[] = $key;
                }
            }
        }

        // merge any previous required fields with current ones
        $requiredFields = array_merge($this->getOperationSetting('requiredFields') ?? [], $requiredFields);

        // since this COULD BE called twice (to support the previous syntax where developers needed to call `setValidation` after the field definition)
        // and to make this change non-breaking, we are going to return an unique array. There is NO WARM returning repeated names, but there is also
        // no sense in doing it, so array_unique() it is.
        $requiredFields = array_unique($requiredFields);

			$this->setOperationSetting('requiredFields', $requiredFields);
    }

    /**
     * Check the current object to see if an input is required
     * for the given operation.
     *
     * @param  string  $inputKey  Field or input name.
     * @param  string  $operation  create / update
     * @return bool
     */
    public function isRequired($inputKey)
    {
        if (! $this->hasOperationSetting('requiredFields')) {
            return false;
        }
		if(is_array($inputKey)){
			foreach($inputKey as $k){
					if (Str::contains($k, '.')) {
						$inputKey = Str::dotsToSquareBrackets($inputKey, ['*']);
					}
			}
		}else{
			if (Str::contains($inputKey, '.')) {
				$inputKey = Str::dotsToSquareBrackets($inputKey, ['*']);
			}
		}

        return in_array($inputKey, $this->getOperationSetting('requiredFields'));
    }

    /**
     * Add the validation setup by developer in field `validationRules` to the  validation.
     *
     * @param  array  $field  - the field we want to get the validation from.
     * @param  bool|string  $parent  - the parent name when setting up validation for subfields.
     */
    private function setupFieldValidation($field, $parent = false)
    {
        [$rules, $messages] = $this->getValidationRulesAndMessagesFromField($field, $parent);

        if (! empty($rules)) {
            $this->setValidation($rules, $messages);
        }
    }

    /**
     * Return the array of rules and messages with the validation key accordingly set
     * to match the field or the subfield accordingly.
     *
     * @param  array  $field  - the field we want to get the rules and messages from.
     * @param  bool|string  $parent  - the parent name when setting up validation for subfields.
     */
    private function getValidationRulesAndMessagesFromField($field, $parent = false)
    {
        $rules = [];
        $messages = [];

        foreach ((array) $field['name'] as $fieldName) {
            if ($parent) {
                $fieldName = $parent.'.*.'.$fieldName;
            }

            if (isset($field['validationRules'])) {
                $rules[$fieldName] = $field['validationRules'];
            }
            if (isset($field['validationMessages'])) {
                foreach ($field['validationMessages'] as $validator => $message) {
                    $fieldValidationName = $fieldName.'.'.$validator;
                    $messages[$fieldValidationName] = $message;
                }
            }
        }

        return [$rules, $messages];
    }
}
