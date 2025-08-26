<?php
//print_r($stack);
//dd($Amer->buttons()->where('stack', $stack));
?>
@if ($Amer->buttons()->where('stack', $stack)->count())
	@foreach ($Amer->buttons()->where('stack', $stack) as $button)
	<?php
	$aos=$entry ?? null;
	if($aos !== null){$button->getHtml($entry ?? null);}
	?>
	  {!! $button->getHtml($entry ?? null) !!}
	@endforeach
@endif
