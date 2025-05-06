<?php

namespace App\Data;

use App\Helpers\Number;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class StudentParsedData extends Data
{
    public function __construct(
        #[MapInputName('numele')]
        public string     $name,

        #[MapInputName('prenumele')]
        public string     $surname,

        #[MapInputName('grupa'), MapOutputName('group')]
        public string     $group,

        #[MapInputName('anul_de_studii'), MapOutputName('studyYear')]
        public string|int $studyYear,

        #[MapInputName('specialitatea'), MapOutputName('speciality')]
        public string     $speciality,

        #[MapInputName('patronimicul')]
        public string     $thirdName = '',
    )
    {
        $this->name = "$name $surname $thirdName";

        $this->studyYear = is_numeric($this->studyYear) ? (int)$this->studyYear : Number::romanicToArabic(trim($this->studyYear));;
    }
}
