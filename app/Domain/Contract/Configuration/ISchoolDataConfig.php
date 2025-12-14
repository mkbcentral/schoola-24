<?php

namespace App\Domain\Contract\Configuration;

interface ISchoolDataConfig
{
    /**
     * Summary of getSectionList
     *
     * @param  int  $option_filer
     */
    public static function getSectionList(): mixed;

    /**
     * Summary of getOPtionList
     */
    public static function getOptionList(int $per_page = 10): mixed;

    /**
     * Summary of getClassRoomList
     *
     * @param  mixed  $option_filer
     */
    public static function getClassRoomList(
        ?int $option_filer,
        ?string $sortBy,
        ?bool $sortAsc,
        int $per_page = 10,
    ): mixed;
}
