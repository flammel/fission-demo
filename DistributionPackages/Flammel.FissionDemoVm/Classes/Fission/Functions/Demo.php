<?php
namespace Flammel\FissionDemoVm\Fission\Functions;

use Flammel\Fission\Exception\FissionException;
use Flammel\Fission\Functions\FissionFunction;
use Flammel\FissionDemoVm\Fission\Functions\Demo\Image;
use Flammel\FissionDemoVm\Fission\Functions\Demo\Nav;
use Neos\Flow\Annotations as Flow;

class Demo implements FissionFunction
{
    /**
     * @Flow\Inject
     * @var Image
     */
    protected $image;

    /**
     * @Flow\Inject
     * @var Nav
     */
    protected $nav;

    /**
     * @param mixed ...$args
     * @return mixed|void
     * @throws FissionException
     */
    public function invoke(...$args)
    {
        return $this;
    }

    /**
     * @param array $args
     * @return array
     * @throws \Neos\Flow\Mvc\Routing\Exception\MissingActionNameException
     * @throws \Neos\Media\Exception\AssetServiceException
     * @throws \Neos\Media\Exception\ThumbnailServiceException
     */
    public function image(array $args): array
    {
        return $this->image->getData($args);
    }

    /**
     * @return array
     */
    public function nav()
    {
        return $this->nav->getData();
    }
}
