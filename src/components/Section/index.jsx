import React from 'react';
import Card from '../Card';
import './Section.scss';

function Section({ slug, title, content, cards }) {
    console.log(cards)
    return (
        <div className='section-container' >
            <div className='title-content-container'>
                <h2>{title}</h2>

                <div
                    className='content-dangerously'
                    dangerouslySetInnerHTML={{ __html: content }}
                />
            </div>

            {slug == "section_cards_images" && (
                <div className='cards-carousel-container'>
                    {cards?.map(card => (
                        <Card {...card} />
                    ))}
                </div>
            )}
        </div>
    )
}

export default Section