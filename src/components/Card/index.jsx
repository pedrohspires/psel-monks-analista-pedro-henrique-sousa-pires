import React from 'react'
import './Card.scss'

function Card({ title, content, imageUrl }) {
    return (
        <div className='card-container' >
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

export default Card