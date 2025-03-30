import React from 'react'
import './CardImage.scss'

function CardImage({ title, content, imageUrl }) {
    return (
        <div className='card-image-container' >
            {imageUrl && <img src={imageUrl} />}
            <div className='content'>
                <h2>{title}</h2>

                <div
                    className='content-dangerously'
                    dangerouslySetInnerHTML={{ __html: content }}
                />
            </div>
        </div>
    )
}

export default CardImage