<a href="{{ route('character.view.sheet', ['character_id' => $row->ceoID]) }}">
  {!! img('character', $row->ceoID, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
  {{ $row->ceoName }}
</a>
