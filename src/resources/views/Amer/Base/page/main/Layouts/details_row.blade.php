        @foreach($Amer->columns() as $column)
        <div class="row">
          <div class="col-sm-3  btn-dark border border-light">{!! $column['label'] !!}</div>
          <div class="col-sm border border-light">

            @if(!isset($column['type']))
              @include(listview('columns.text'))
            @else
              @if(!view()->exists(listview('columns.'.$column['type'])))
                @include(listview('columns.text'))
              @else
                @include(listview('columns.'.$column['type']))
              @endif
            @endif
          </div>
        </div>

        @endforeach
