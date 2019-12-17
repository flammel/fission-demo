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
     * @param array $args
     * @return mixed|void
     * @throws FissionException
     */
    public function invoke(array $args = [])
    {
        return $this;
    }

    /**
     * @param array $props
     * @return array
     * @throws \Neos\Flow\Mvc\Routing\Exception\MissingActionNameException
     * @throws \Neos\Media\Exception\AssetServiceException
     * @throws \Neos\Media\Exception\ThumbnailServiceException
     */
    public function image(array $props): array
    {
        return $this->image->getData($props);
    }

    /**
     * @return array
     */
    public function nav()
    {
        return $this->nav->getData();
    }
}
