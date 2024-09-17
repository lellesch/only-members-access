import { useDispatch, useSelect } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
import { NoticeList } from '@wordpress/components';

const Notices = () => {
	const { removeNotice } = useDispatch( noticesStore );
	const notices = useSelect( ( select ) =>
		select( noticesStore ).getNotices()
	);

	return notices.length > 0 ? (
		<NoticeList notices={notices} onRemove={removeNotice} />
	) : null;
};

export { Notices };
