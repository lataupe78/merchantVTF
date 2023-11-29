
const formatPrice = (value, numberDigits = 2) => {

  if (typeof value !== "number") {
    return value;
  }
  // const formatter = new Intl.NumberFormat('en-US', {
  const formatter = new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
    minimumFractionDigits: numberDigits
  });
  return formatter.format(value);
}


export { formatPrice } 