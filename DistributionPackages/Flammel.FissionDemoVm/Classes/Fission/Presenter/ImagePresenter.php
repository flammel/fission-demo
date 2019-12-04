<?php
namespace Flammel\FissionDemoVm\Fission\Presenter;

use Flammel\Fission\Service\FissionContext;
use Flammel\Fission\Zweig\TemplatePath\NeosNamingConventionTemplatePath;
use Flammel\Zweig\Component\ComponentArguments;
use Flammel\Zweig\Component\ComponentContext;
use Flammel\Zweig\Component\ComponentName;
use Flammel\Zweig\Presenter\Presentable;
use Flammel\Zweig\Presenter\Presenter;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Routing\Exception\MissingActionNameException;
use Neos\Media\Domain\Model\ThumbnailConfiguration;
use Neos\Media\Domain\Service\AssetService;
use Neos\Media\Exception\AssetServiceException;
use Neos\Media\Exception\ThumbnailServiceException;

class ImagePresenter implements Presenter
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
     * @param ComponentName $name
     * @param ComponentArguments $arguments
     * @return Presentable
     * @throws \Exception
     */
    public function present(ComponentName $name, ComponentArguments $arguments): Presentable
    {
        return new Presentable(
            new NeosNamingConventionTemplatePath($name),
            new ComponentContext($this->getContextData($arguments[0]))
        );
    }

    /**
     * @param array $arguments
     * @return array
     * @throws AssetServiceException
     * @throws MissingActionNameException
     * @throws ThumbnailServiceException
     */
    private function getContextData(array $arguments): array
    {
        return [
            'src' => $this->getSrc($arguments),
            'alt' => $arguments['alt'] ?? '',
            'title' => $arguments['title'] ?? ''
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
