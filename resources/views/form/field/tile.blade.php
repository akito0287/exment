<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div id="tile-{{$column}}" class="row-eq-height row tile-group" style="margin:0;">
            @foreach($options as $option)
            <div class="col-xs-12 col-sm-6 tile-group-item">
                <div id="tile-{{$column}}-{{$loop->index}}" class="row tile" data-id="{{array_get($option, 'id')}}">
                    @if(array_key_exists('thumbnail', $option))
                    <div class="col-xs-4 tile-thumbnail">
                        <img src="{{ array_get($option, 'thumbnail') }}" />
                    </div>
                    @endif
                    <div class="{{ array_key_exists('thumbnail', $option) ? " col-xs-8" : "col-xs-12" }}">
                        <p class="tile-title">{{ array_get($option, 'title') }}</p>
                        @if(array_key_exists('description', $option))
                        <p class="tile-description">{{ array_get($option, 'description') }}</p>
                        @endif
                        @if(array_key_exists('author', $option))
                        <p class="tile-author">{{ array_get($option, 'author') }}</p>
                        @endif
                    </div>
                    <input type="hidden" class="tile-value" name="{{$name}}[]" />
                </div>
            </div>
            @endforeach
        </div>

        @include('admin::form.help-block')

    </div>
</div>

{{-- TODO:move to css file --}}
<style type="text/css">
    .tile {
        background-color: #fff;
        cursor: pointer;
        border:1px solid #ddd;
        margin: 5px -10px;
        width: 100%;
    }
        .tile:hover {
            background-color: #ccf2ff;
        }
        .tile.active {
            background-color: #32ccff;
        }
        .tile p{
            margin:10px 0;
        }
        .tile .tile-thumbnail img {
            max-width: 100%;
            max-height: 100%;
            margin: 0 auto;
            padding: 10px 0;
            display: block;
        }
        .tile .tile-title {
            font-size: 1.1em;
            font-weight: bold;
        }
        .tile .tile-description {
            font-size: 0.9em;
        }

        .row-eq-height, .row-eq-height .tile-group-item {
            display: flex;
            flex-wrap: wrap;
        }
        
</style>