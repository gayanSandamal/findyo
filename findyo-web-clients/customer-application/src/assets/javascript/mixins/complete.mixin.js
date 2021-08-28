export default {
  data () {
    return {
      completion: [
        {
          fields: [
            {
              required: true,
              type: 'single-text',
              label: 'Username'
            },
            {
              label: 'First Name',
              required: true,
              type: 'single-text'
            },
            {
              type: 'single-text',
              label: 'Last Name'
            },
            {
              type: 'single-date',
              label: 'Date of Birth'
            },
            {
              label: 'Job Title',
              type: 'single-text'
            },
            {
              value: [
                {
                  type: 'tel',
                  value: ''
                }
              ],
              type: 'numeric-array',
              label: 'Contact Numbers'
            },
            {
              type: 'single-email',
              label: 'email'
            }
          ],
          page_name: 'Basic Information'
        },
        {
          page_name: 'Location details',
          fields: [
            {
              type: 'single-text',
              label: 'Address'
            },
            {
              type: 'input-array',
              label: 'Area',
              value: [
                {
                  type: 'single-text',
                  label: 'City'
                },
                {
                  label: 'District',
                  type: 'single-text'
                },
                {
                  type: 'single-text',
                  label: 'Province'
                },
                {
                  type: 'single-text',
                  label: 'Country'
                },
                {
                  type: 'single-numeric',
                  label: 'Postal code'
                }
              ]
            }
          ]
        },
        {
          fields: [
            {
              label: 'About you',
              type: 'single-textarea'
            },
            {
              label: 'Skills',
              type: 'text-array'
            },
            {
              label: 'Preferences and Interests',
              type: 'text-array'
            }
          ],
          page_name: 'Profile description'
        }
      ]
    }
  }
}
