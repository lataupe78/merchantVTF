const getArrayValues = (source, key = "roles") => {
  // populate roles / permissions from user model

  if (source?.[key] == undefined) {
    return [];
  }

  if (typeof source?.[key] === "string") {
    return [Number(source[key])];
  }

  let attributes = [];
  source[key].forEach((attribute) => {
    attributes.push(Number(attribute));
  });
  return attributes;
};

export { getArrayValues }