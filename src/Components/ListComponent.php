<?php

namespace Crazed\Crudwired\Components;

use Illuminate\Database\Eloquent\Model;

class ListComponent extends Component
{
    public $perPage = 15;
    protected $listeners = ['$refresh', 'infiniteScroll'];

    public function query()
    {
        return Model::all();
    }

    public function getInfiniteScrollProperty()
    {
        return $this->query()->count() > $this->perPage;
    }

    public function infiniteScroll()
    {
        $this->perPage += 15;
    }
}
