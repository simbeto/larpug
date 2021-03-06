<?php

namespace Larpug;
use Illuminate\View\Engines\EngineInterface;
use Illuminate\View\Factory;
use Illuminate\Contracts\Container\Container; 
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Pug extends Factory implements EngineInterface {

  protected $app;

  public function __construct(Container $app = null) {
    $this->app = $app;
  }

  public function get($path, array $data=[]) {
    $result = $this->compile($path, $data);
    if ($result['data'] === false) {
      return 'Pug Compilation Erorr: '.json_encode($result);
    }
    return $result['data'];
  }

  public function compile($path, $data) {

    $data['pretty'] = true;
    $data['self'] = true;

    $result = Node::post('pug', 'http://localhost:4242/',['file' => $path], $data);

    if ($result['status'] == 500) {
      trigger_error('Pug Error: '.$result['data']);
    }

    return $result;

  }

}
