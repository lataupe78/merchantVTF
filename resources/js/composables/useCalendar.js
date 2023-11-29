import { computed } from "vue";
import { DateTime, Interval, Info } from "luxon";

/**
 * props needs :
 *  dates
 * ranges
 *  
 */
export default function useCalendar(props, type = "month") {
  // type can be year, month, week ..
  const allowedTypes = ['year', 'month', 'week', 'day']
  if (allowedTypes.includes(type) !== true) {
    throw new Error('type Calendar should be in : ' + allowedTypes.join(', '))
  }

  const currentPeriod = computed(() => {
    if (props.dates?.current === null) {
      console.error("failed get current date from props", {
        date: props.dates,
      });
      return null;
    }
    let date = props.dates.current;
    let currentDate = DateTime.fromFormat(date, "yyyy-MM-dd");

    let datas = {
      start: currentDate.startOf(type),
      end: currentDate.endOf(type),
    };

    datas.type = type
    datas.year = datas.start.year;
    datas.month = datas.start.month;
    datas.weekNumber = datas.start.weekNumber;
    datas.urlDate = datas.start.toFormat("yyyy-MM-dd");
    datas.label = datas.start.toFormat("MMMM yyyy", { locale: "fr" });

    return datas;
  });


  const firstDay = computed(() => {
    let day = currentPeriod.value.start?.weekday;
    if (day === null || day == undefined) {
      console.error(
        "failed get currentPeriod start weekday date from currentPeriod",
        { currentPeriod }
      );
      return null;
    }

    let offsetDays = day - 1; // différence entre lundi et le jour du 1er

    return currentPeriod.value.start.minus({ days: offsetDays });
  });

  const lastDay = computed(() => {
    let day = currentPeriod.value?.end?.weekday;
    if (day === null || day == undefined) {
      console.error(
        "failed get currentPeriod end weekday date from currentPeriod",
        { currentPeriod }
      );
      return null;
    }

    let offsetDays = (7 - day) % 7; // différence entre lundi et le jour du 1er

    return currentPeriod.value.end.plus({ days: offsetDays });
  });

  const rangeDates = computed(() => {
    if (firstDay.value == null || lastDay.value == null) {
      console.error("failed currentPeriod", { firstDay, lastDay });
      return [];
    }
    let interval = Interval.fromDateTimes(firstDay.value, lastDay.value);

    // for (var d of days(interval)) {
    //   console.log(d.toISO());
    // }

    let range = interval.splitBy({ day: 1 }).map((d) => {
      let labelData = d.start.toFormat("yyyy-MM-dd");

      // if($businessDays.value)
      // let isBusiness = $businessDays?.days?.includes(labelData) == true
      let isBusiness = true;

      const diff = d.start.diff(DateTime.now(), ["days"]).toObject();

      let periodType = currentPeriod.value?.type || 'month'


      let curDay = {
        weekNumber: d.start.weekNumber,
        month: d.start.month,
        year: d.start.year,

        weekday: Info.weekdays("short", { locale: "fr" })[d.start.weekday],

        label: d.start.toLocaleString("DATETIME_MED_WITH_WEEKDAY"),
        labelLong: d.start.toFormat("DDDD"),
        labelShort: d.start.toFormat("dd"),

        labelData: labelData,

        dateShort: d.start.toLocaleString("DATE_SHORT"),

        start: d.start,
        end: d.end,
        month: d.start.month,


        isBusiness: isBusiness,
        diffFromToday: Math.floor(diff.days),

        isSelected: false,

        ranges: props.ranges?.[labelData],


      };


      switch (periodType) {
        case "year":
          curDay.isActive = d.start?.year == currentPeriod.value?.year
          break;
        case "month":
          curDay.isActive = d.start?.month == currentPeriod.value?.month
          break;
        case "week":
          curDay.isActive = d.start?.weekNumber == currentPeriod.value?.weekNumber
        case "day":
          curDay.isActive = d.start?.day == currentPeriod.value?.day
          break;
        default:

      }
      return curDay
    });

    // debugger
    return range;
  });

  const weekCount = computed(() => {
    return rangeDates.value.length / 7;
  });


  const currentRange = (indexWeek) => {
    // let currentRangeDates = Array.from(rangeDates.value)
    //   .slice(indexWeek, indexWeek + 7)

    let currentRangeDates = rangeDates.value.slice(indexWeek, indexWeek + 7);

    // console.log({ currentRangeDates, indexWeek })

    return currentRangeDates;
  };


  return {
    currentPeriod,
    firstDay,
    lastDay,
    rangeDates,
    weekCount,
    currentRange,
  }
}


