<div class="table-responsive" style="padding-top: 30px;">
    <table class="table table-data" id="table-data">
        <thead>
        <tr>
            @foreach($_table->columns as $column)
                <th @if($column->type == 3) class="text-right" @endif>
                    @if($column->type == 4)
                        <input type="checkbox" name="all" value="{{ $item->{$column->field} ?? '' }}">
                    @else
                        {{ $column->title }}
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($_table->data as $num => $item)
            <tr data-key="{{ $item[$_table->key] ?? '' }}">
                @foreach($_table->columns as $column)
                    @switch($column->type)
                        @case(4)
                            <td {!! $column->attrs($item) !!}>
                                <input type="checkbox" name="key" value="{{ $column->format($item) }}">
                            </td>
                            @break
                        @case(1)
                            <td {!! $column->attrs($item) !!}>{!! $column->format($item) !!}</td>
                            @break
                        @case(2)
                            <td>
                                <a class="btn btn-primary btn-xs" {!! $column->attrs($item) !!}
                                        {!! $column->url($item) !!}>{{ $column->title }}</a>
                            </td>
                            @break
                        @case(5)
                            <td>
                                <a class="table-link" {!! $column->url($item) !!} {!! $column->attrs($item) !!}>{{ $column->format($item) }}</a>
                            </td>
                            @break
                        @case(6)
                        <td><label {!! $column->attrs($item) !!}>{!! $column->format($item) !!}</label></td>
                        @break
                        @case(3)
                            <td class="text-right">
                               @foreach($column->subs as $key => $sub)
                                   @if((count($column->subs) > 2 && $key < 1) || count($column->subs) <= 2)
                                    <a class="op-btn btn btn-xs {{ $sub->class ?? '' }}" {!! $sub->url($item) !!} {!! $sub->attrs($item) !!}
                                       data-op="{{ $sub->field ?? '' }}" >{{ $sub->title ??'' }}</a>
                                    @endif
                               @endforeach
                                @if(count($column->subs) > 2)
                                   <span @if($_table->data->count() - $num < 2) class="dropup" @else class="dropdown" @endif>
                                       <a class="btn btn-success btn-xs dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" >更多
                                           <span class="caret"></span>
                                       </a>
                                       <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1" style="min-width: 0;">
                                               <li class="row" style="padding: 5px;margin: 0px;">
                                               <div style="    white-space: nowrap;">
                                               @foreach($column->subs as $key => $sub)
                                                   @if($key > 0)
                                                       <button class="op-btn btn {{ $sub->class ?? '' }} btn-xs" {!! $sub->url($item) !!} {!! $sub->attrs($item) !!}
                                                       data-op="{{ $sub->field ?? '' }}" >{{ $sub->title ??'' }}</button>
                                                   @endif
                                               @endforeach
                                               </div>
                                               </li>
                                       </ul>
                                   </span>
                               @endif
                            </td>
                            @break
                    @endswitch
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="text-right">
    @if(method_exists($_table->data, 'links'))
        {{$_table->data->appends(request()->all())->links()}}
    @endif
</div>

