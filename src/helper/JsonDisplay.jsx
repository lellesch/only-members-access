const JsonDisplay = (data) => {

    const res = data !== undefined ? data : 'No Data';

    return (
        <pre>{JSON.stringify(res, null, 2)}</pre>
    );
};

export default JsonDisplay;
