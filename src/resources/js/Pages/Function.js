
export const onDelete = async (values, toast, store, route, commit, message) => {

    const requestOptions = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            // "Authorization": "Bearer " + USER_DATA.token.bearer ?? 0, // worl fine without token
        },
        body: JSON.stringify(values)
    };
    fetch(window.location.origin + `/api/backend/${route}/delete`, requestOptions)
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                for (const field in data.errors) {
                    toast.add({severity: 'error', summary: data.errors[field][0], life: 3000});
                }
            } else {
                toast.add({severity: 'info', summary: message, detail: values.value, life: 3000});
                store.commit(commit);
            }
        });
};