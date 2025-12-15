import React from 'react'

type Props = { id: string; name: string; duration: string; price: string }

export default function CourseCard({ id, name, duration, price }: Props) {
  return (
    <div className="card">
      <h3>{name}</h3>
      <p>Duration: {duration}</p>
      <p>Price: {price}</p>
      <small className="muted">Course ID: {id}</small>
    </div>
  )
}
