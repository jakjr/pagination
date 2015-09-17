<?php

namespace Jakjr\Pagination;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class PaginationSeviceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Paginator::currentPageResolver(function ($pageName = 'page') {

            $keeper = $this->app->make('keeper', [$this->app['request']->path()]);

            if ($this->app['request']->has($pageName)) {

                $currentPage = $this->app['request']->input($pageName);

                //Persisto o pagina corrente
                $keeper->keep([$pageName => $currentPage]);

                return $currentPage;
            }

            //Utilizo o pagina persistida em algum momento anterior.
            $currentPage = $keeper->get($pageName);

            //Ou retorno 1
            return $currentPage ?: 1;
        });
    }
}
