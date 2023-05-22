<?php

namespace App\Services\Timetable;

use App\Models\Timetable;
use App\Models\TimetableTimeSlot;

class TimeSlotService
{
    /**
     * Create timetable time slot.
     *
     * @param Timetabke $timetable
     * @param mixed     $data
     *
     * @return void
     */
    public function createTimeSlot($data)
    {
        TimetableTimeSlot::create([
            'start_time'   => $data['start_time'],
            'stop_time'    => $data['stop_time'],
            'timetable_id' => $data['timetable_id'],
        ]);
    }

    public function deleteTimeSlot(TimetableTimeSlot $timeSlot)
    {
        $timeSlot->delete();
    }

    /**
     * Create timetable time record.
     *
     * @param TimetableTimeSlot $timeSlot
     * @param mixed             $data
     *
     * @return void
     */
    public function createTimetableRecord(TimetableTimeSlot $timeSlot, $data)
    {
        //remove existing record
        if ($timeSlot->weekdays->find($data['weekday_id']) || $data['id'] == null) {
            $timeSlot->weekdays()->detach($data['weekday_id']);
        }

        if ($data['id'] != null) {
            $timeSlot->weekdays()->attach($data['weekday_id'], ['timetable_time_slot_weekdayable_id' => $data['id'], 'timetable_time_slot_weekdayable_type' => $data['type']]);
        }
    }
}
