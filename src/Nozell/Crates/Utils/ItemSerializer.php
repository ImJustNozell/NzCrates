<?php

namespace Nozell\Crates\Utils;

use pocketmine\item\Item;
use pocketmine\nbt\BigEndianNbtSerializer;
use pocketmine\nbt\TreeRoot;
use pocketmine\item\VanillaItems;

final class ItemSerializer {
    public static function serialize(Item $item): string {
        $nbtHandler = new BigEndianNbtSerializer();
        $serializedData = $nbtHandler->write(new TreeRoot($item->nbtSerialize()));
        return self::sanitizeString($serializedData);
    }

    public static function deserialize(string $data): Item {
        $cleanData = self::sanitizeString($data);

        if (!mb_check_encoding($cleanData, "UTF-8")) {
            var_dump("Invalid Serialized Item");
            return VanillaItems::AIR();
        }

        $nbtHandler = new BigEndianNbtSerializer();
        return Item::nbtDeserialize($nbtHandler->read($cleanData)->mustGetCompoundTag());
    }

    private static function sanitizeString(string $data): string {
        return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
    }
}
