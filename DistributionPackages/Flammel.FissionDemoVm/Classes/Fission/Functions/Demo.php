<?php
namespace Flammel\FissionDemoVm\Fission\Functions;

use Flammel\Fission\Exception\FissionException;
use Flammel\Fission\Functions\FissionFunction;
use Flammel\Fission\ValueObject\WrappedNode;
use Flammel\FissionDemoVm\Fission\Functions\Demo\Image;
use Flammel\FissionDemoVm\Fission\Functions\Demo\Nav;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;

class Demo
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
     * @return $this
     */
    public function __invoke(array $args = [])
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
     * @param WrappedNode $documentNode
     * @return array
     */
    public function nav(WrappedNode $documentNode)
    {
        return $this->nav->getData($documentNode);
    }
}
