import { NextApiRequest, NextApiResponse } from 'next'
import prisma from '../../../../lib/prisma'

export default async function handler(req: NextApiRequest, res: NextApiResponse) {
  const { slug } = req.query as { slug: string }

  if (req.method === 'GET') {
    const link = await prisma.link.findUnique({ where: { customSlug: slug } })
    if (!link) return res.status(404).json({ error: 'Not found' })
    return res.json(link)
  }

  if (req.method === 'POST') {
    // increment click count
    const updated = await prisma.link.update({
      where: { customSlug: slug },
      data: { clickCount: { increment: 1 } },
    })
    return res.json(updated)
  }

  res.status(405).json({ error: 'Method not allowed' })
}
