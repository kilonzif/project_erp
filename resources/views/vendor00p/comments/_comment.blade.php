@inject('markdown', 'Parsedown')

@if(isset($reply) && $reply === true)
  <div id="comment-{{ $comment->id }}" >
@else
  <dt id=" comment-{{ $comment->id }}" style=" font-weight: 400;">
@endif
    <div class="media-body">
        @if($comment->updated_at!=$comment->created_at)
        <h5 class="mt-0 mb-1">
            {{ $comment->commenter->name }}
            <small class="text-muted">{{ $comment->updated_at->diffForHumans() }}</small>
        </h5>
        @else
         <h5 class="mt-0 mb-1">
             {{ $comment->commenter->name }}
             <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
         </h5>
        @endif

        <div style="white-space: pre-wrap;">{!! $markdown->line($comment->comment) !!}</div>
        <p>
            @can('reply-to-comment', $comment)
                <button data-toggle="modal" data-target="#reply-modal-{{ $comment->id }}" class="btn btn-sm btn-link text-uppercase">Reply</button>
            @endcan
            @can('edit-comment', $comment)
                <button data-toggle="modal" data-target="#comment-modal-{{ $comment->id }}" class="btn btn-sm btn-link text-uppercase">Edit</button>
            @endcan
            @can('delete-comment', $comment)
                <a href="{{ url('comments/' . $comment->id) }}" onclick="event.preventDefault();document.getElementById('comment-delete-form-{{ $comment->id }}').submit();"
                   class="btn btn-sm btn-link text-danger text-uppercase">Delete</a>
                <form id="comment-delete-form-{{ $comment->id }}" action="{{ url('comments/' . $comment->id) }}" method="POST" style="display: none;">
                    @method('DELETE')
                    @csrf
                </form>
            @endcan
            <hr>
        </p>

        @can('edit-comment', $comment)
            <div class="modal fade" id="comment-modal-{{ $comment->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="{{ url('comments/' . $comment->id) }}">
                            @method('PUT')
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Comment</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="message">Update your message here:</label>
                                    <textarea required class="form-control" name="message" rows="3">{{ $comment->comment }}</textarea>
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        @can('reply-to-comment', $comment)
            <div class="modal fade" id="reply-modal-{{ $comment->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="{{ url('comments/' . $comment->id) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Reply to Comment</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="message">Enter your message here:</label>
                                    <textarea required class="form-control" name="message" rows="3"></textarea>
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">Reply</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        {{-- Margin bottom --}}
        @foreach($comment->children as $child)
            @include('comments::_comment', [
                'comment' => $child,
                'reply' => true
            ])
        @endforeach
    </div>
@if(isset($reply) && $reply === true)
  </div>
@else
  </dt>
@endif