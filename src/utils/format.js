export const formatPhone = (value) => {
    value = value.replace(/\D/g, "");
    console.log(value)
    if (value.length > 11) value = value.substring(0, 11);

    if (value.length > 7) {
        return `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
    } else if (value.length > 2) {
        return `(${value.substring(0, 2)}) ${value.substring(2)}`;
    } else if (value.length > 0) {
        return `(${value}`;
    }

    return value;
};