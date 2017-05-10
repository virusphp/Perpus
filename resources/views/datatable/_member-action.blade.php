{!! Form::model($member, ['route' => ['members.destroy', $member->id], 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => 'Yakin mau menghapus member'.$member->name. '?']) !!}
    <a href="{{ route('members.show', $member->id)}}">Lihat data peminjam</a> |
    {!! Form::submit('Hapus', ['class' => 'btn btn-xs btn-danger']) !!}
{!! Form::close() !!}