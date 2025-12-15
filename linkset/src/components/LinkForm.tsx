import React, { useState } from 'react'
import axios from 'axios'

// Simple form to create a short link
export default function LinkForm() {
  const [originalUrl, setOriginalUrl] = useState('')
  const [customSlug, setCustomSlug] = useState('')
  const [message, setMessage] = useState<string | null>(null)

  async function handleGenerate(e: React.FormEvent) {
    e.preventDefault()
    setMessage(null)
    try {
      const res = await axios.post('/api/links/create', { originalUrl, customSlug: customSlug || undefined })
      const link = res.data
      setMessage(`Short link created: ${window.location.origin}/${link.customSlug}`)
      setOriginalUrl('')
      setCustomSlug('')
      // dispatch event so list can refresh
      window.dispatchEvent(new Event('link-created'))
    } catch (err: any) {
      setMessage(err?.response?.data?.error || 'Error creating link')
    }
  }

  return (
    <form onSubmit={handleGenerate} style={{ maxWidth: 720 }}>
      <div style={{ marginBottom: 8 }}>
        <label>Original URL</label>
        <input
          style={{ width: '100%', padding: 8 }}
          value={originalUrl}
          onChange={(e) => setOriginalUrl(e.target.value)}
          placeholder="https://example.com/very/long/url"
        />
      </div>

      <div style={{ marginBottom: 8 }}>
        <label>Custom alias (optional)</label>
        <input
          style={{ width: '100%', padding: 8 }}
          value={customSlug}
          onChange={(e) => setCustomSlug(e.target.value)}
          placeholder="jadwal-uts-del"
        />
      </div>

      <button type="submit" style={{ padding: '8px 16px' }}>
        Generate
      </button>

      {message && <p style={{ marginTop: 12 }}>{message}</p>}
    </form>
  )
}
