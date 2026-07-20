import PostalMime from 'postal-mime';

export default {
  async email(message, env, ctx) {
    const ENDPOINT = env.ENDPOINT || 'https://firstbidin.com/api/inbound-email/';
    const SECRET   = env.INBOUND_SECRET;
    try {
      const parser = new PostalMime();
      const raw = await new Response(message.raw).arrayBuffer();
      const email = await parser.parse(raw);
      const payload = {
        to: (message.to || '').toLowerCase(),
        from: email.from?.address || message.from || '',
        subject: email.subject || '',
        html: email.html || email.text || '',
      };
      const res = await fetch(ENDPOINT + SECRET, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });
      if (!res.ok) console.log('FirstBid endpoint error', res.status);
    } catch (e) {
      console.log('Worker parse error', e.message);
    }
  },
};
