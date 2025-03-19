@extends('layouts.app')

@section('title', 'Notifications - ISI')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Notifications</h3>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="mb-3">
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-double"></i> Tout marquer comme lu
                        </button>
                    </form>
                    <form action="{{ route('notifications.destroyAll') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer toutes les notifications ?')">
                            <i class="fas fa-trash"></i> Tout supprimer
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                                <tr class="{{ $notification->read_at ? '' : 'table-primary' }}">
                                    <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $notification->data['message'] }}</td>
                                    <td>
                                        @if(!$notification->read_at)
                                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-info">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune notification</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 