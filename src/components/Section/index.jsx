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
        if (slug == "section_images")
            setHeightColumn1(refColumn1?.current?.offsetHeight - 3);
    }

    function getCustomStyle() {
        if (slug == "section_app_links") {
            return {
                backgroundColor: "#3C0C60",
                borderRadius: "24px",
                color: "#EAE8E4",
                padding: "40px",
                display: "flex",
                gap: "40px",
            }
        }

        if (slug == "section_images") {
            return {
                width: "100%",
                display: "grid",
                gridTemplateColumns: "repeat(2, minmax(0, 1fr))",
                gap: "16px"
            }
        }

        return {};
    }

    return (
        <div className={`section-container`}>
            <div style={getCustomStyle()}>
                <div style={{
                    flex: 1,
                    display: slug == "section_app_links" ? "flex" : "",
                    alignItems: slug == "section_app_links" ? "center" : ""
                }}>
                    <div ref={refColumn1}>
                        <div
                            style={{ marginBottom: slug == "section_app_links" ? 0 : 16 }}
                            className='title-content-container'
                        >
                            <h2
                                style={{
                                    margin: slug == "section_app_links" ? "0 0 8px 0" : "8px 0 8px 0"
                                }}
                            >
                                {title}
                            </h2>

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

                {slug == "section_app_links" && (
                    <div className='app-links-container'>
                        <a href='https://www.apple.com/br/app-store/' target='_blank'>
                            <img src='src\assets\images\appstore.png' />
                        </a>

                        <a href='https://play.google.com/store/games?hl=pt_BR' target='_blank'>
                            <img src='src\assets\images\googleplay.png' />
                        </a>
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