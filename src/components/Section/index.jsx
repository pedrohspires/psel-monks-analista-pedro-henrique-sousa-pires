import React, { useEffect, useRef, useState } from 'react';
import Card from '../Card';
import './Section.scss';

function Section({ slug, title, content, cards, sectionImages }) {
    const refColumn1 = useRef();
    const [heightColumn1, setHeightColumn1] = useState();

    useEffect(() => {
        setColumn2Height();
        window.addEventListener("resize", setColumn2Height);

        return () => {
            window.removeEventListener("resize", setColumn2Height);
        };
    }, [refColumn1]);

    function setColumn2Height() {
        if (slug == "section_images") {
            console.log(refColumn1?.current?.offsetHeight)
            setHeightColumn1(refColumn1?.current?.offsetHeight - 3);
        }
    }

    return (
        <div className={`section-container`}>
            <div
                style={slug == "section_images" ? {
                    width: "100%",
                    display: "grid",
                    gridTemplateColumns: "repeat(2, minmax(0, 1fr))",
                    gap: "16px"
                } : {}}
            >
                <div>
                    <div ref={refColumn1}>
                        <div className='title-content-container'>
                            <h2>{title}</h2>

                            <div
                                className='content-dangerously'
                                dangerouslySetInnerHTML={{ __html: content }}
                            />
                        </div>

                        {slug == "section_images" && (
                            <img
                                style={{
                                    width: "100%",
                                    objectFit: "cover",
                                    borderRadius: "8px",
                                    aspectRatio: "1 / 1"
                                }}
                                src={sectionImages[0]}
                            />
                        )}
                    </div>
                </div>

                {!!(slug == "section_images") && (
                    <div
                        className='section-images-column'
                        style={{ height: heightColumn1 }}
                    >
                        <div style={{ height: "calc(100%)" }}>
                            <img src={sectionImages[1]} />
                        </div>

                        <div style={{ height: "calc(100%)" }}>
                            <img src={sectionImages[2]} />
                        </div>
                    </div>
                )}
            </div>

            {slug == "section_cards_images" && (
                <div className='cards-carousel-container'>
                    {cards?.map((card, index) => (
                        <Card key={index + "_" + card.title} {...card} />
                    ))}
                </div>
            )}
        </div>
    )
}

export default Section