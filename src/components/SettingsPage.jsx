import {
    __experimentalHeading as Heading,
    __experimentalVStack as VStack,
    __experimentalSpacer as Spacer,
    Card,
    CardHeader,
    CardBody,
    Button,
    TextControl,

} from '@wordpress/components';
import {__} from "@wordpress/i18n";
import useSettings from "../hooks/useSettings";
import {MyCheckboxControl, MySpinnerContent, MyRadioControl, MyTextControl} from "./Controls";
import {Notices} from "./Notices";

export const SettingsPage = () => {


    const {settings, setSettings, loading, saveSettings} = useSettings();


    const optionsRestAccess = [
        {label: __('Zugang für alle verfügbar', 'only-members-access'), value: 'full_website'},
        {label: __('Zugang nur für angemeldete Benutzer', 'only-members-access'), value: 'only_logged_in'},
    ]


    const handleButtonSaveSettings = () => {
        saveSettings(settings)
    }

    return (
        <>
            <VStack spacing="7">
                <Heading level={1}>
                    {__('Only Members Access Einstellungen', 'only-members-access')}
                </Heading>
                <Notices/>
                <Card>
                    <CardHeader>
                        <Heading level={4}>{__('Registrierung', 'only-members-access')}</Heading>
                    </CardHeader>

                    {loading ?
                        (<MySpinnerContent/>)
                        : (
                            <CardBody>
                                <MyCheckboxControl
                                    label={__('Jeder kann sich registrieren', 'only-members-access')}
                                    help={__('Dies ist eine Standard-WordPress-Option, die hier platziert wird, um eine einfache Änderung zu ermöglichen.', 'only-members-access')}
                                    checked={settings.users_can_register}
                                    onChange={(value) => setSettings({...settings, users_can_register: value})}

                                />

                            </CardBody>
                        )}

                </Card>

                <Card>
                    <CardHeader>
                        <Heading level={4}>{__('Login', 'only-members-access')}</Heading>
                    </CardHeader>
                    {loading ? (<MySpinnerContent/>) : (
                        <CardBody>
                            <VStack>
                                <MyCheckboxControl
                                    label={__('Weiterleitung nach dem Login aktivieren', 'only-members-access')}
                                    checked={settings.enable_redirection_after_login}
                                    onChange={(value) => setSettings({
                                        ...settings, enable_redirection_after_login: value
                                    })}
                                />
                                <TextControl
                                    value={settings.url_redirection_after_login}
                                    onChange={(value) => setSettings({...settings, url_redirection_after_login: value})}
                                    __nextHasNoMarginBottom
                                    help={__('Hier kann die URL angegeben werden, zu der man nach der erfolgreichen Anmeldung weitergeleitet wird.', 'only-members-access')}
                                />
                            </VStack>
                        </CardBody>
                    )}

                </Card>

                <Card>
                    <CardHeader>
                        <Heading level={4}>{__('REST-API', 'only-members-access')}</Heading>
                    </CardHeader>
                    {loading ? (<MySpinnerContent/>) : (
                        <CardBody>
                            <MyRadioControl
                                option={settings.rest_api_access}
                                options={optionsRestAccess}
                                onChange={(value) => setSettings({...settings, rest_api_access: value})}
                            />
                        </CardBody>
                    )}

                </Card>

                <Card>
                    <CardHeader>
                        <Heading level={4}>{__('Seiten / Beitrag Ausnahmen', 'only-members-access')}</Heading>
                    </CardHeader>

                    {
                        loading ? (
                            <MySpinnerContent/>
                        ) : (
                            <CardBody>
                                <MyTextControl
                                    value={Array.isArray(settings.post_exceptions_ids) && settings.post_exceptions_ids.length > 0 ? settings.post_exceptions_ids.join(',') : ''}
                                    label={__('Seiten-/Beitrags-IDs', 'only-members-access')}
                                    help={__('Hier können Seiten-IDs eingegeben werden, die öffentlich zugänglich sein sollen.', 'only-members-access')}
                                    onChange={(value) => {
                                        const updatedIds = value.split(',').map(id => id.trim());
                                        setSettings({...settings, post_exceptions_ids: updatedIds});
                                    }}
                                />
                            </CardBody>
                        )
                    }

                </Card>

            </VStack>

            <Spacer marginTop="5"/>

            {loading ? (
                <Button variant="primary">{__('Wird geladen...', 'only-members-access')}</Button>
            ) : (
                <Button onClick={handleButtonSaveSettings}
                        variant="primary">{__('Speichern', 'only-members-access')}</Button>
            )}
        </>
    )
}
