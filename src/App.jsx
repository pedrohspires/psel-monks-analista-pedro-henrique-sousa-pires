import { useEffect, useState } from "react";
import Section from "./components/Section";
import SectionForm from "./components/SectionForm";
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
		if (response.status !== 200) {
			setLoading(false);
			return;
		}

		const sectionsFormated = await getSectionsFormated(response);
		setSections(sectionsFormated);
	}

	async function getSectionsFormated(response) {
		const sectionsResponse = response.data;

		if (!sectionsResponse?.length)
			return [];

		const sectionsFormated = [];
		for (let section of sectionsResponse) {
			sectionsFormated.push({
				slug: section.slug,
				title: section.title?.rendered || "",
				content: section.content?.rendered || "",
				cards: section.cards?.map(card => ({
					title: card.title,
					content: card.content,
					imageUrl: card.image_url,
					textBtn: card.button_text
				})),
				sectionImages: section.section_images,
				categories: await getCategories(section)
			})
		}

		return sectionsFormated;
	}

	async function getCategories(section) {
		if (section.slug == "section_categories") {
			const response = await api.get('/wp-json/wp/v2/categories');
			if (response.status == 200)
				return response.data.map(category => category.name);
		}

		return [];
	}

	return (
		<div>
			{sections?.length && <SectionPrimary {...sections.find(x => x.slug == "section_primary")} />}

			{sections?.filter(section => section.slug !== "section_primary" && section.slug !== "section_form").map((section, index) => (
				<Section key={index + "_" + section.title} {...section} />
			))}

			{sections?.length && <SectionForm {...sections.find(x => x.slug == "section_form")} />}
		</div>
	);
}

export default App;