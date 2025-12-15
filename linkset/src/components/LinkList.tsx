import React, { useEffect, useState } from 'react'
import axios from 'axios'

type Link = {
  id: number
  originalUrl: string
  customSlug: string
  clickCount: number
  createdAt: string
}

export default function LinkList() {
  const [links, setLinks] = useState<Link[]>([])

  async function fetchLinks() {
    const res = await axios.get('/api/links/list')
    setLinks(res.data)
  }

  useEffect(() => {
    fetchLinks()
    function onCreated() { fetchLinks() }
    window.addEventListener('link-created', onCreated)
    return () => window.removeEventListener('link-created', onCreated)
  }, [])

  async function openShort(link: Link) {
    // Increment click counter then open
    await axios.post(`/api/links/${link.customSlug}`)
    window.open(`/${link.customSlug}`, '_blank')
    fetchLinks()
  }

  return (
    <div>
      <h3>Daftar Link</h3>
      {links.length === 0 && <p>Tidak ada link.</p>}
      <ul>
        {links.map((l) => (
          <li key={l.id} style={{ marginBottom: 8 }}>
            <div><strong>{l.customSlug}</strong> — <a href={l.originalUrl} target="_blank">{l.originalUrl}</a></div>
            <div>Clicks: {l.clickCount} • Created: {new Date(l.createdAt).toLocaleString()}</div>
            <button onClick={() => openShort(l)} style={{ marginTop: 6 }}>Open short</button>
          </li>
        ))}
      </ul>
    </div>
  )
}
