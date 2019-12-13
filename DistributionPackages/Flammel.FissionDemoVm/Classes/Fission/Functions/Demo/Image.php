<?php
namespace Flammel\FissionDemoVm\Fission\Functions\Demo;

use Flammel\Fission\Service\FissionContext;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Routing\Exception\MissingActionNameException;
use Neos\Media\Domain\Model\ThumbnailConfiguration;
use Neos\Media\Domain\Service\AssetService;
use Neos\Media\Exception\AssetServiceException;
use Neos\Media\Exception\ThumbnailServiceException;

class Image
{
    /**
     * @Flow\Inject
     * @var AssetService
     */
    protected $assetService;

    /**
     * @Flow\Inject
     * @var FissionContext
     */
    protected $fissionContext;

    /**
     * @param array $args
     * @return array
     * @throws AssetServiceException
     * @throws MissingActionNameException
     * @throws ThumbnailServiceException
     */
    public function getData(array $args): array
    {
        return [
            'src' => $this->getSrc($args),
            'alt' => $args['alt'] ?? '',
            'title' => $args['title'] ?? ''
        ];
    }

    /**
     * @param array $arguments
     * @return string
     * @throws AssetServiceException
     * @throws MissingActionNameException
     * @throws ThumbnailServiceException
     */
    private function getSrc(array $arguments): string
    {
        $config = $this->getThumbnailConfiguration($arguments);
        $request = $this->fissionContext->getActionRequest();
        $sizeAndUri = $this->assetService->getThumbnailUriAndSizeForAsset($arguments['asset'], $config, $request);
        return $sizeAndUri['src'];
    }

    /**
     * @param array $arguments
     * @return ThumbnailConfiguration
     */
    private function getThumbnailConfiguration(array $arguments): ThumbnailConfiguration
    {
        $config = new ThumbnailConfiguration(
            $arguments['width'] ?? null,
            $arguments['maximumWidth'] ?? null,
            $arguments['height'] ?? null,
            $arguments['maximumHeight'] ?? null,
            $arguments['allowCropping'] ?? false,
            $arguments['allowUpScalint'] ?? false,
            $arguments['async'] ?? true,
            $arguments['quality'] ?? null,
            $arguments['format'] ?? null
        );
        return $config;
    }
}
