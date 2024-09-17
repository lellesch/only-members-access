import {useEffect, useState} from "@wordpress/element";
import {CheckboxControl, RadioControl, Spinner, TextControl} from "@wordpress/components";

const MyCheckboxControl = ({label, help, checked, onChange}) => {
    const [isChecked, setChecked] = useState(checked || true);

    useEffect(() => {
        setChecked(checked);
    }, [checked]);

    const handleChange = (value) => {
        setChecked(value)
        onChange(value)
    }

    return (
        <CheckboxControl
            __nextHasNoMarginBottom
            label={label}
            help={help}
            checked={isChecked}
            onChange={handleChange}
        />
    );
}

const MySpinnerContent = () => {
    return (
        <Spinner
            style={{
                padding: '10px',
                height: 'calc(3px * 7)',
                width: 'calc(3px * 7)'
            }}
        />
    )
}

const MyRadioControl = ({option, options, onChange}) => {
    const [isOption, setOption] = useState(option);

    useEffect(() => {
        setOption(option);
    }, [option]);

    const changeHandler = (value) => {
        setOption(value)
        onChange(value)
    }

    return (
        <RadioControl
            label="Restrict REST-API access"
            selected={isOption}
            options={options}
            onChange={changeHandler}
        />
    );
};

const MyTextControl = ({label, value, onChange, help = ''}) => {

    const [inputValue, setInputValue] = useState(value)

    useEffect(() => {
        setInputValue(value)
    }, [value]);

    function changeHandler(newValue) {
        setInputValue(newValue);
        onChange(newValue);
    }

    return (
        <TextControl
            value={inputValue}
            label={label}
            help={help}
            onChange={changeHandler}
        />
    )
}

export {MyCheckboxControl, MySpinnerContent, MyRadioControl, MyTextControl};
