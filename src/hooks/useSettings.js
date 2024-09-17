import {useEffect, useState} from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import {useDispatch} from "@wordpress/data";
import {store as noticesStore} from '@wordpress/notices';
import {__} from "@wordpress/i18n";
import defaultSettings from "../config/defaultSettings";

const useSettings = () => {
    const [settings, setSettings] = useState(defaultSettings);

    const {createSuccessNotice, createErrorNotice} = useDispatch(noticesStore);

    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const loadSettings = () => {
        setLoading(true);
        setError(null);

        apiFetch({
            method: 'GET',
            path: '/only-members-access/v1/settings',
        })
            .then((data) => {
                //console.log('loadingData::',data)
                setSettings({
                    users_can_register: data?.users_can_register ?? false,
                    enable_redirection_after_login: data?.enable_redirection_after_login ?? false,
                    url_redirection_after_login: data?.url_redirection_after_login ?? '',
                    rest_api_access: data?.rest_api_access ?? 'only_logged_in',
                    post_exceptions_ids: data?.post_exceptions_ids ?? []
                });
                setLoading(false);
            })
            .catch((err) => {
                setError('Error when saving');
                setLoading(false);
            });
    };


    const saveSettings = (newSettings) => {
        setLoading(true);
        setError(null);

        apiFetch({
            path: '/only-members-access/v1/settings',
            method: 'POST',
            data: newSettings,
        })
            .then((response) => {
                //console.log('Einstellungen erfolgreich gespeichert:', response);
                const new_data = response?.data || newSettings
                setSettings(response.data);
                setLoading(false);
                createSuccessNotice(
                    __('Einstellungen gespeichert.', 'only-members-access')
                );
            })
            .catch((err) => {
                console.error('Fehler beim Speichern der Einstellungen:', err);
                //setError('Fehler beim Speichern der Einstellungen');
                setLoading(false);
                createErrorNotice(
                    __('Fehler beim speichern.', 'only-members-access')
                );
            });
    };

    useEffect(() => {
        loadSettings();
    }, []);

    return {
        settings,
        setSettings,
        loading,
        error,
        saveSettings
    };
};

export default useSettings;
