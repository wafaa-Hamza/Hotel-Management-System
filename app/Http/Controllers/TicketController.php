<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use App\Models\TicketFile;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
//        $this->authorize('view-ticket');

        $tickets = Ticket::with('user', 'ticketFiles' /* , 'categories', 'labels', 'assignedToUser' */)
        /* ->when($request->has('status'), function (Builder $query) use ($request) {
        return $query->where('status', $request->input('status'));
        })
        ->when($request->has('priority'), function (Builder $query) use ($request) {
        return $query->withPriority($request->input('priority'));
        })
        ->when($request->has('category'), function (Builder $query) use ($request) {
        return $query->whereRelation('categories', 'id', $request->input('category'));
        })
        ->when(auth()->user()->hasRole('agent'), function (Builder $query) {
        $query->where('assigned_to', auth()->user()->id);
        })
        ->when(auth()->user()->hasRole('user'), function (Builder $query) {
        $query->where('user_id', auth()->user()->id);
        })
        ->latest() */

            ->get();

        return response()->json($tickets);
    }
    public function ticketPagination(Request $request)
    {
//        $this->authorize('view-ticket');

        $tickets = Ticket::with('user' /* , 'categories', 'labels', 'assignedToUser' */)
        /* ->when($request->has('status'), function (Builder $query) use ($request) {
        return $query->where('status', $request->input('status'));
        })
        ->when($request->has('priority'), function (Builder $query) use ($request) {
        return $query->withPriority($request->input('priority'));
        })
        ->when($request->has('category'), function (Builder $query) use ($request) {
        return $query->whereRelation('categories', 'id', $request->input('category'));
        })
        ->when(auth()->user()->hasRole('agent'), function (Builder $query) {
        $query->where('assigned_to', auth()->user()->id);
        })
        ->when(auth()->user()->hasRole('user'), function (Builder $query) {
        $query->where('user_id', auth()->user()->id);
        })
        ->latest() */
            ->paginate(request()->segment(count(request()->segments())));

        return response()->json($tickets);
    }
    public function store(TicketRequest $request)
    {
//        $this->authorize('create-ticket');

        $ticket = auth()->user()->tickets()->create($request->only('title', 'message', 'status', 'priority'));

        /*  $ticket->attachCategories($request->input('categories'));

        $ticket->attachLabels($request->input('labels')); */

        /* if ($request->input('assigned_to')) {
        $ticket->assignTo($request->input('assigned_to'));
        User::find($request->input('assigned_to'))->notify(new AssignedTicketNotification($ticket));
        } else {
        User::role('admin')
        ->each(fn ($user) => $user->notify(new NewTicketCreatedNotification($ticket)));
        } */

        if (!is_null($request->file('files'))) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $name = time() . '.' . $file->getClientOriginalName();
                $file->move('upload', $name);
                TicketFile::create(['file' => 'upload/' . $name, 'ticket_id' => $ticket->id]);
            }
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($ticket)
            ->withProperties(['attributes' => $ticket])
            ->event('created')
            ->log('Ticket has been created');

        return response()->json($ticket);
    }

    public function show(Ticket $ticket)
    {
//        $this->authorize('view-ticket');

//        $this->authorize('view', $ticket);
        //$ticketfile = TicketFile::with('ticket')->get();
        $ticketwithfiles = $ticket->with(['ticketFile'])->where('id', $ticket->id)->get();

        return response()->json($ticketwithfiles);
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
//        $this->authorize('edit-ticket');

        //$this->authorize('update', $ticket);#
        //activity()->disableLogging();

        $ticket->update($request->only('title', 'message', 'status', 'comment', 'priority', 'is_resolved', 'is_locked'));
        /*  activity()
        ->causedBy(auth()->user())
        ->performedOn($ticket)
        ->withProperties(['attributes' =>$ticket,'old'=>$old])
        ->event('updated')
        ->log('Ticket has been updated'); */
       // $ticket->syncCategories($request->input('categories'));

       // $ticket->syncLabels($request->input('labels'));

        /* if ($ticket->wasChanged('assigned_to')) {
        User::find($request->input('assigned_to'))->notify(new AssignedTicketNotification($ticket));
        } */

        return response()->json($ticket);

    }
    public function destroy(Ticket $ticket)
    {
//        $this->authorize('delete-ticket');

//        $this->authorize('delete', $ticket);

        $ticket->ticketFiles()->delete();
        $ticket->delete();

        return response()->json("deleted");
    }

    public function upload(Request $request)
    {
//        $this->authorize('upload-ticket');

        /* $path = [];
        if ($request->file) {
        foreach ($request->file as $file) {
        $path[] = $file->store('tmp', 'public');
        }
        } */
        if (!is_null($request->file('files'))) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $name = time() . '.' . $file->getClientOriginalName();
                $file->move('upload', $name);
                TicketFile::create(['file' => 'upload/' . $name, 'ticket_id' => 1]);
            }
        }
        //return response()->json(['data'=>$path]);
    }
    public function close(Ticket $ticket)
    {
//        $this->authorize('close-ticket');

        //$this->authorize('update', $ticket);

        $ticket->close();

        return response()->json("closed");
    }

    public function reopen(Ticket $ticket)
    {
//        $this->authorize('reopen-ticket');

        //$this->authorize('update', $ticket);

        $ticket->reopen();

        return response()->json("reopened");
    }

    public function archive(Ticket $ticket)
    {
//        $this->authorize('archive-ticket');

        //$this->authorize('update', $ticket);

        $ticket->archive();

        return response()->json("archived");
    }

}
