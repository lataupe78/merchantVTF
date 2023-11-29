import { DateTime } from 'luxon'

const getDateTime = (date = null) => {
  if (date == null || date == undefined) {
    return
  }

  let dateTime

  dateTime = DateTime.fromJSDate(new Date(date), { zone: 'utc' })
  if (dateTime.invalid == null) {
    return dateTime
  }
  console.error(date, dateTime.invalidReason, dateTime.invalidExplanation)

  dateTime = DateTime.fromISO(date, { setZone: true })
  if (dateTime.invalid == null) {
    return dateTime
  }
  console.error(date, dateTime.invalidReason, dateTime.invalidExplanation)

  // dateTime = DateTime.fromJSDate(date, { setZone: true })
  // if (dateTime.invalid == null) {
  //   return dateTime
  // }
  // console.error(date, dateTime.invalidReason, dateTime.invalidExplanation)

  return date
}


const dateLabel = (date = null, format = 'DATETIME_MED_WITH_WEEKDAY') => {

  if (date == null || date == undefined) {
    return
  }


  const dateTime = getDateTime(date)
  // console.log(dateTime);
  if (dateTime?.invalid === null || dateTime?.isValid === true) {
    return dateTime
      .setLocale('fr')
      .setZone('Europe/Paris')
      .toLocaleString(DateTime[format]) // DATETIME_SHORT);
  }

  console.log('unrecognized date format', date)
  return dateTime
}


const toFormat = (date = null, format = 'yyyy-MM-dd') => {
  if (date == null || date == undefined) {
    return
  }


  const dateTime = getDateTime(date)
  // console.log(dateTime);
  if (dateTime?.invalid === null || dateTime?.isValid === true) {
    return dateTime.setLocale('fr').toFormat(format)
  }

  console.log('unrecognized date format', date)
  return dateTime
}


export { getDateTime, dateLabel, toFormat }