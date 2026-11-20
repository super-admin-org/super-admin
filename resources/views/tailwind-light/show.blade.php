<div class="space-y-4">
    <div>
        {!! $panel !!}
    </div>

    @foreach($relations as $relation)
        <div>
            {!!  $relation->render() !!}
        </div>
    @endforeach
</div>
