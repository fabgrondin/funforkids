<?php

namespace App\Http\Controllers;

use App\Repositories\EventRepository;
use App\Repositories\TagRepository;
use App\Http\Requests\EventCreateRequest;
use App\Http\Requests\EventUpdateRequest;
use Illuminate\Http\Request;
use App\Event;
use Intervention\Image\ImageManagerStatic as Image;

class EventController extends Controller
{
    protected $eventRepository;

    protected $nbrPerPage = 6;

    public function __construct(EventRepository $eventRepository)
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('userManage', ['only' => ['edit', 'destroy']]);
        $this->eventRepository = $eventRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = $this->eventRepository->getAgenda();

        return view('events.liste', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventCreateRequest $request, TagRepository $tagRepository)
    {
        if ($request->has('image')) {
            $path = $request->file('image')->store('images');

            // Resize the image
            $thumbnail = Image::make(public_path('storage/app/').$path);
            $thumbnail->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $thumbnail->save();

            $inputs = array_merge($request->all(), ['user_id' => $request->user()->id, 'path_image' => $path]);
        } else {
            $inputs = array_merge($request->all(), ['user_id' => $request->user()->id]);
        }

        $inputs['place']=substr($inputs['place'], 0, -8); // Remove France from address

        $event = $this->eventRepository->store($inputs);

        if (isset($inputs['tags'])) {
            $tagRepository->store($event, $inputs['tags']);
        }

        return redirect()->route('event.show', ['event'=>$event->id])->withMessage('L\'événement "' .$inputs['title']. '" a été créé');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = $this->eventRepository->getById($id);

        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = $this->eventRepository->getById($id);

        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventUpdateRequest $request, Event $event, TagRepository $tagRepository)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $inputs = array_merge($request->all(), ['path_image' => $path]);
        } else {
            $inputs = $request->all();
        }
        $this->eventRepository->update($event->id, $inputs);

        if (isset($inputs['tags'])) {
            $tagRepository->update($event, $inputs['tags']);
        }

        return redirect()->route('user.events')->withMessage('L\'événement "' .$request->input('title'). '" a été modifié');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->eventRepository->destroy($id);

        return redirect()->route('user.events')->withMessage('L\'événement a été supprimé');
    }

    public function userEvents(Request $request)
    {
        $events = $this->eventRepository->getByUserId($request->user()->id);

        return view('events.userEvents', compact('events'));
    }
}
