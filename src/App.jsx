import { useEffect, useState } from "react";
import Section from "./components/Section";
import api from "./utils/api";

function App() {
	const [sections, setSections] = useState([]);
	const [loading, setLoading] = useState(true);

	useEffect(() => {
		init();
	}, []);

	async function init() {
		setLoading(true);
		await getSections();

		setLoading(false);
	}

	async function getSections() {
		const response = await api.get('/wp-json/wp/v2/sections');
		if (response.status !== 200)
			setLoading(false);

		const sectionsFormated = getSectionsFormated(response);
		setSections(sectionsFormated);
	}

	function getSectionsFormated(response) {
		const sectionsResponse = response.data;

		if (!sectionsResponse?.length)
			return [];

		return sectionsResponse.map(x => ({
			slug: x.slug,
			title: x.title?.rendered || "",
			content: x.content?.rendered || ""
		}));
	}

	return (
		<div>
			{sections?.map(x => (
				<Section {...x} />
			))}
		</div>
	);
}

export default App;