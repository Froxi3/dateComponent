<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

    if($this->StartResultCache()){
        if($_POST["number"]){ 
            $arResult = [];
            $number = $_POST["number"];
            $currentDay = ConvertDateTime(ConvertTimeStamp(time()), "YYYY-MM-DD", "ru");

            $date = date("d.m.Y");
            $dayWeek = date('N', strtotime($date));
            $dayCount = $number + $dayWeek;
            $weekCount = floor($dayCount/5);
            //Количество выходных
            $holiday_count = ($dayCount % 5 > 0) ? 0 : 2;
            //Количество дней в целом
            $weekDay = $weekCount * 7 - $day_week + ($dayCount % 5) - $holiday_count;
            //Крайняя дата
            $dateEnd = date("d.m.Y", strtotime($date . " + $weekDay day"));
            //Крайний день
            $dateEndCount = date('N', strtotime($dateEnd));
            $holidayShift = $dateEndCount > 5 ? 7 - $dateEndCount + 1 : 0;
            $finalDate = date("d.m.Y", strtotime($dateEnd . " + $holidayShift day"));

            if(!\Bitrix\Main\Loader::IncludeModule("iblock")){
                $this->AbortResultCache();
                ShowError("Модуль инфоблоков не установлен");
                return;
            }
            $rsElements = CIBlockElement::GetList(
                ["PROPERTY_HOLIDAY"=>"ASC"],
                Array(
                    "ACTIVE" => "Y",
                    "IBLOCK_ID" => 1,
                    ">PROPERTY_HOLIDAY" => $currentDay
                ),
                false,
                false,
                [
                    "PROPERTY_HOLIDAY"
                ]
            );
            while($arElement = $rsElements->GetNext()) {
                $arResult["ITEMS"][] = $arElement;
            }
            //Пересчет с учетом праздников
            for ($i=0; $i < count($arResult["ITEMS"]); $i++) { 
                if(strtotime($arResult["ITEMS"][$i]["PROPERTY_HOLIDAY_VALUE"]) <= strtotime($finalDate)){
                    //Если праздничный день выпал на рабочий
                    if(date( 'N', strtotime( $arResult["ITEMS"][$i]["PROPERTY_HOLIDAY_VALUE"])) < 6 ){
                        $finalDate = date("d.m.Y", strtotime($finalDate . "+" . 1 ." day "));
                        if(date( 'N', strtotime($finalDate)) == 6){
                            $finalDate = date("d.m.Y", strtotime($finalDate . "+" . 2 ." day "));
                        }
                        if(date( 'N', strtotime($finalDate)) == 7){
                            $finalDate = date("d.m.Y", strtotime($finalDate . "+" . 1 ." day "));
                        }
                    }
                }
            }
            $arResult["finalDate"] = $finalDate;
        }
        $this->IncludeComponentTemplate();        
    }
?>