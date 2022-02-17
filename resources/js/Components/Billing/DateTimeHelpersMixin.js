export default {
    methods: {
        getFormattedDate,
        getFormattedTime
    }
}

export function getFormattedDate(date) {
    if (! date) {
        return null;
    }
    return new Date(date).toLocaleDateString();
}

export function getFormattedTime(date) {
    if (! date) {
        return null;
    }
    return new Date(date).toLocaleTimeString().slice(0, 5);
}
