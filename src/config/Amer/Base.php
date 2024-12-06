<?php
return
		[
			'package_path'=>base_path().'/vendor/Amerhendy/Amer/src',
			'create'=>[
				'contentClass' => 'col-md-8 bold-labels',
				'tabsType' => 'horizontal',
				'groupedErrors' => true,
				'inlineErrors'  => true,
				'autoFocusOnFirstField' => true,
				'defaultSaveAction' => 'save_and_back',
				'showSaveActionChange' => true,
				'showCancelButton' => true,
				'warnBeforeLeaving' => false,
			],
			'list'=>
			[
				'contentClass' => 'col-md-12',
				'responsiveTable' => true,
				'persistentTable' => true,
				'searchableTable' => true,
				'persistentTableDuration' => false,
				'defaultPageLength' => 10,
				'pageLengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Amer::crud.all']],
				'actionsColumnPriority' => 1,
				'lineButtonsAsDropdown' => false,
				'resetButton' => true,
				'searchOperator' => 'like',
				'showEntryCount' => true,

			],
			'show'=>
			[
				'contentClass' => 'col-md-8',
				'setFromDb'  => true,
				'timestamps' => true,
				'softDeletes' => false,// soft deleted items & add a deleted_at column to ShowOperation?
				'tabsEnabled' => false,
				'tabsType' => 'horizontal',
			],
			'update'=>
			[
				'contentClass' => 'col-md-8 bold-labels',
				'tabsType' => 'horizontal',
				'groupedErrors' => true,
				'inlineErrors'  => true,
				'autoFocusOnFirstField' => true,
				'defaultSaveAction' => 'save_and_back',
				'showSaveActionChange' => true,
				'showCancelButton' => true,
				'warnBeforeLeaving' => false,
			],
			'reorder'=>
			[
				'contentClass'   => 'col-md-8 col-md-offset-2',
			],
			'locales'=>['ar','en'],
			'show_translatable_field_icon'     => true,
    		'translatable_field_icon_position' => 'right',

		];
