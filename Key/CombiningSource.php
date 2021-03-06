<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 8/3/15
 * Time: 6:05 PM
 */

namespace Giftcards\Encryption\Key;

class CombiningSource extends AbstractSource
{
    const LEFT = 'left';
    const RIGHT = 'right';
    
    protected $internalSource;
    protected $leftsAndRights;

    /**
     * CombiningSource constructor.
     * @param array $leftsAndRights
     * @param SourceInterface $internalSource
     */
    public function __construct(array $leftsAndRights, SourceInterface $internalSource)
    {
        $this->internalSource = $internalSource;

        foreach ($leftsAndRights as $key => $leftsAndRight) {
            if (!isset($leftsAndRight[self::LEFT]) || !isset($leftsAndRight[self::RIGHT])) {
                throw new \InvalidArgumentException(sprintf(
                    'All values for the $leftAndRights array must have a left and right key. The value for key "%s" does not.',
                    $key
                ));
            }
        }

        $this->leftsAndRights = $leftsAndRights;
    }

    public function has($key)
    {
        return
            isset($this->leftsAndRights[$key])
            && $this->internalSource->has($this->leftsAndRights[$key][self::LEFT])
            && $this->internalSource->has($this->leftsAndRights[$key][self::RIGHT])
        ;
    }

    public function getKey($key)
    {
        return
            $this->internalSource->get($this->leftsAndRights[$key][self::LEFT])
            .$this->internalSource->get($this->leftsAndRights[$key][self::RIGHT])
        ;
    }
}
