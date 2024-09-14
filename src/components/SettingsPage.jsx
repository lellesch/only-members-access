import {
    __experimentalHeading as Heading,
    __experimentalText as Text
} from '@wordpress/components';
import {__} from "@wordpress/i18n";

export const SettingsPage = () => {

    return (
        <>
            <Heading level={1}>
                {__('My Settings Page', 'only-members-access')}
                <Text>Es geht...</Text>
            </Heading>
        </>
    )
}
