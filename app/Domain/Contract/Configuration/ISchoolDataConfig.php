<?php

namespace App\Domain\Contract\Configuration;

interface ISchoolDataConfig
{
    /**
     * Summary of getSectionList
     * @param int $option_filer
     * @return mixed
     */
    public static function getSectionList(): mixed;
    /**
     * Summary of getOPtionList
     * @return mixed
     */
    public static function getOptionList(int $per_page = 10): mixed;

    /**
     * Summary of getClassRoomList
     * @param mixed $option_filer
     * @param string $sortBy
     * @param bool $sortAsc
     * @param int $per_page
     * @return mixed
     */
    public static function getClassRoomList(
        ?int $option_filer,
        ?string $sortBy,
        ?bool $sortAsc,
        int $per_page = 10,

    ): mixed;
}
