<?php

namespace EventEspresso\core\domain\entities\editor\blocks\widgets;

use EventEspresso\core\domain\entities\editor\Block;
use EventEspresso\core\domain\entities\editor\blocks\CoreBlocksAssetManager;
use EventEspresso\core\domain\entities\shortcodes\EspressoEvents;

/**
 * Class EventsList
 * Displays a list of events
 *
 * @package EventEspresso\core\domain\entities\editor\blocks\widgets
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EventsList extends Block
{

    const BLOCK_TYPE = 'widgets-events-list';

    /**
     * @var EspressoEvents $shortcode
     */
    protected $shortcode;


    /**
     * EventsList constructor.
     *
     * @param CoreBlocksAssetManager $block_asset_manager
     * @param EspressoEvents $shortcode
     */
    public function __construct(CoreBlocksAssetManager $block_asset_manager, EspressoEvents $shortcode)
    {
        parent::__construct($block_asset_manager);
        $this->shortcode = $shortcode;
    }


    /**
     * Perform any early setup required by the block
     * including setting the block type and supported post types
     *
     * @return void
     */
    public function initialize()
    {
        $this->setBlockType(self::BLOCK_TYPE);
        $this->setSupportedPostTypes(array('post', 'page'));
        $this->setAttributes(array());
        $this->setDynamic();
    }


    /**
     * returns the rendered HTML for the block
     *
     * @param array $attributes
     * @return string
     * @throws \EE_Error
     * @throws \EventEspresso\core\exceptions\InvalidDataTypeException
     * @throws \EventEspresso\core\exceptions\InvalidInterfaceException
     * @throws \InvalidArgumentException
     */
    public function renderBlock(array $attributes = array())
    {
        return '<h2>' . __METHOD__ . '()</h2>' . var_export($attributes, true)
               . $this->shortcode->processShortcode($attributes);
    }
}
