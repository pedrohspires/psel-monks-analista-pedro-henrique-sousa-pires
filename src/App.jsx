import { useEffect, useState } from "react";
import Section from "./components/Section";
import SectionPrimary from "./components/SectionPrimary";
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

		return sectionsResponse.map(section => ({
			slug: section.slug,
			title: section.title?.rendered || "",
			content: section.content?.rendered || "",
			cards: section.cards?.map(card => ({
				title: card.title,
				content: card.content
			}))
		}));
	}

	return (
		<div>
			{sections.length && <SectionPrimary {...sections[0]} />}

			{sections?.slice(1).map(section => (
				<Section {...section} />
			))}
		</div>
	);
}

export default App;