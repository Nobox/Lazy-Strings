<?php

namespace Nobox\LazyStrings\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Nobox\LazyStrings\LazyStrings;

class LazyStringsController extends Controller
{

    /**
     * The current lazy strings instance.
     *
     * @var Nobox\LazyStrings\LazyStrings
     */
    private $lazyStrings;

    /**
     * Data to pass to the view.
     *
     * @var array
     */
    private $data = [];

    /**
     * Let's get this thing started.
     *
     * @return void
     */
    public function __construct()
    {
        $this->lazyStrings = App::make('Nobox\LazyStrings\LazyStrings');
    }

    /**
     * Deploy LazyStrings.
     *
     * @return Illuminate\Http\Response
     */
    public function deploy()
    {
        $this->lazyStrings->generate();

        $metadata = $this->lazyStrings->getMetadata();

        $this->data['lazyVersion'] = LazyStrings::VERSION;
        $this->data['refreshedBy'] = $metadata['refreshedBy'];
        $this->data['refreshedOn'] = $metadata['refreshedOn'];

        return view('lazy-strings::lazy', $this->data);
    }
}
